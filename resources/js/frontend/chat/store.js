import Vuex from 'vuex'
import Vue from 'vue'
import parseQuery from './modules/parse-query'
import {objectToFormData} from './modules/object-to-form-data'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        account: null,
        accounts: [],
        meta: {}
    },
    mutations: {
        updateMeta(state) {
            state.meta = parseQuery(location.search)
        },

        updateAccount(state) {
            const {meta} = state

            if (!meta.hasOwnProperty('account-type') || !meta.hasOwnProperty('account')) {
                state.account = state.accounts[state.accounts.length - 1]
                return
            }

            let account = null

            state.accounts.some(item => {
                const ok = item.type === meta['account-type'] && item.id.toString() === meta.account
                if (ok) {
                    account = item
                }

                return ok
            })

            state.account = account
        },

        setAccounts(state, accounts) {
            accounts.forEach(account => {
                account.conversationSelected = false
                account.conversations = null
            })
            state.accounts = accounts
        },

        setAccount(state, account) {
            state.account = account
        },

        setConversations(state, {account, conversations}) {
            account.conversations = conversations
        },

        setConversation(state, {account, conversation}) {
            if (conversation) {
                conversation.active = true
            }
            account.conversationSelected = conversation
        },

        deActivateConversation(state, conversation) {
            conversation.active = false
        }
    },
    actions: {
        createConversation(state, {account, target, targetType}) {
            return new Promise(resolve => {

                fetch(window.routes.addConversation, {
                    method: 'post',
                    body: objectToFormData({
                        id: account.id,
                        type: account.type,
                        target,
                        targetType,
                        _token: window.csrf
                    })
                }).then(response => response.json().then(conversation => {
                    conversation.active = false
                    account.conversations.unshift(conversation)
                    resolve(conversation)
                }))

            })
        },
        loadMessages(state, {account, conversation}) {
            conversation.page = conversation.page + 1

            fetch(window.routes.getMessages, {
                method: 'post',
                body: objectToFormData({
                    id: account.id,
                    type: account.type,
                    cid: conversation.id,
                    _token: window.csrf,
                    page: conversation.page
                })
            }).then(response => response.json().then(archives => {
                if (!conversation.archives) {
                    conversation.archives = {}
                }

                console.log(conversation)

                const regx = /\d{4,}-\d\d-\d\d/gim
                const months = ['January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'
                ]

                archives.forEach(message => {
                    const extracted = message.at.match(regx)[0]
                    let key

                    if (window.dates.today.match(regx)[0] === extracted) {
                        key = 'Today'
                    } else if (window.dates.yesterday.match(regx)[0] === extracted) {
                        key = 'Yesterday'
                    } else {
                        const date = new Date(extracted)
                        key = `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`
                    }

                    if (!conversation.archives.hasOwnProperty(key)) {
                        Vue.set(conversation.archives, key, [])
                    }

                    conversation.archives[key].push(message)
                })

                Vue.prototype.$nextTick(() => {
                    const elm = document.getElementById('chat-section')
                    elm.scrollTop = elm.scrollHeight
                })
            }))
        },
        sendMessage(state, {account, conversation, message}) {
            if (!conversation.archives.hasOwnProperty('Today')) {
                Vue.set(conversation.archives, 'Today', [])
            }

            const msg = {
                id: null,
                at: null,
                mid: conversation.mid,
                msg: message,
                sent: false
            }

            conversation.archives['Today'].push(msg)

            fetch(window.routes.send, {
                method: 'post',
                body: objectToFormData({
                    id: account.id,
                    type: account.type,
                    cid: conversation.id,
                    mid: conversation.mid,
                    txt: message,
                    _token: window.csrf
                })
            }).then(response => response.json().then(data => {
                msg.id = data.id
                msg.at = data.at.date
                msg.sent = true
            }))
        }
    }
})

store.watch(({account}) => account, (account) => {
    if (account.conversations) {
        return
    }

    fetch(`${window.routes.getConversations}?id=${account.id}&type=${account.type}`).then(response => response.json().then(conversations => {
        conversations.forEach(conversation => {
            conversation.archives = null
            conversation.active = false
            conversation.page = 0
        })

        const {state} = store
        const {meta} = state

        store.commit('setConversations', {account, conversations})

        if (meta.hasOwnProperty('target') && meta.hasOwnProperty('target-type') && meta.account === account.id.toString()) {

            const exists = conversations.some(conversation => {
                const ok = conversation.member.userId.toString() === meta.target && conversation.member.type === meta['target-type']

                if (ok) {
                    store.commit('setConversation', {account, conversation})
                    store.dispatch('loadMessages', {account, conversation})
                }

                return ok
            })

            if (!exists) {
                store.dispatch('createConversation', {
                    account,
                    target: meta.target,
                    targetType: meta['target-type']
                }).then(conversation => {
                    store.commit('setConversation', {account, conversation})
                    store.dispatch('loadMessages', {account, conversation})
                })
            }

            return
        }

        if (conversations[0]) {
            store.commit('setConversation', {account, conversation: conversations[0]})
            store.dispatch('loadMessages', {conversation: conversations[0], account})
            return
        }

        store.commit('setConversation', {account, conversation: null})
    }))
})

export default store
