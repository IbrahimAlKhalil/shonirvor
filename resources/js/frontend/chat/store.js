import Vuex from 'vuex'
import Vue from 'vue'
import parseQuery from './modules/parse-query'
import {objectToFormData} from './modules/object-to-form-data'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Pusher = Pusher

const echo = new Echo({
    broadcaster: 'pusher',
    key: '5a3bf510cd645f292be8',
    cluster: 'ap2',
    encrypted: true
})

Vue.use(Vuex)

export function scrollToBottom() {
    const elm = document.getElementById('chat-section')
    elm.scrollTop = elm.scrollHeight
}

function setConversationAttrs(conversation) {
    conversation.archives = null
    conversation.active = false
    conversation.page = 0
    conversation.today = null
}

function subscribe(account, conversation) {
    echo.private(`c-${conversation.id}-${conversation.member.id}`)
        .listen('.m', e => {

            if (!conversation.archives) {
                Vue.set(conversation, 'archives', [
                    {
                        label: 'Today',
                        messages: [e.msg]
                    }
                ])
            } else if (!conversation.today) {
                conversation.archives.unshift({
                    label: 'Today',
                    messages: [e.msg]
                })
            } else {
                conversation.today.messages.unshift(e.msg)
            }

            Vue.nextTick(scrollToBottom)
        })
        .listen('.rc', () => {
            store.commit('removeConversation', {account, conversation})
        })
}

function unSubscribe(conversation) {
    echo.leaveChannel(`c-${conversation.id}-${conversation.member.id}`)
}

const store = new Vuex.Store({
    state: {
        account: null,
        accounts: [],
        meta: {},
        conversation: false
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
                account.conversationSelected = null
                account.conversations = null
            })
            state.accounts = accounts
        },

        setAccount(state, account) {
            state.account = account
        },

        setConversations(state, {account, conversations}) {
            account.conversations = conversations

            conversations.forEach(conversation => subscribe(account, conversation))
        },

        setConversation(state, {account, conversation}) {
            if (conversation) {
                conversation.active = true
            }

            state.conversation = true
            account.conversationSelected = conversation
        },

        deActivateConversation(state, conversation) {
            conversation.active = false
        },

        removeConversation(state, {account, conversation}) {
            const {conversations} = account
            account.conversationSelected = null

            unSubscribe(conversation)
            conversations.splice(conversations.indexOf(conversation), 1)
        },

        deActivateConvLayout(state) {
            state.conversation = false

            state.conversation = false

            state.account.conversationSelected.active = false
            state.account.conversationSelected = null
        }
    },
    actions: {
        createConversation(ctx, {account, target, targetType}) {
            return new Promise(resolve => {

                fetch(window.routes.addConversation, {
                    method: 'POST',
                    body: objectToFormData({
                        id: account.id,
                        type: account.type,
                        target,
                        targetType,
                        _token: window.csrf
                    })
                }).then(response => response.json().then(conversation => {
                    setConversationAttrs(conversation)
                    account.conversations.unshift(conversation)
                    resolve(conversation)
                }))

            })
        },
        loadMessages(ctx, {account, conversation, scroll}) {
            conversation.page = (conversation.page || 0) + 1

            if (!conversation.archives) {
                conversation.archives = false
            }

            fetch(`${window.routes.getMessages}?id=${account.id}&type=${account.type}&cid=${conversation.id}&page=${conversation.page}`).then(response => response.json().then(messages => {
                if (!conversation.archives) {
                    Vue.set(conversation, 'archives', [])
                }

                const regx = /\d{4,}-\d\d-\d\d/gim
                const months = ['January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'
                ]

                const oldArchives = {}

                for (const archive of conversation.archives) {
                    oldArchives[archive.label] = archive.messages
                }

                messages.forEach(message => {
                    const extracted = message.at.match(regx)[0]
                    let key

                    switch (extracted) {
                        case window.dates.today.match(regx)[0]:
                            key = 'Today'
                            break
                        case window.dates.yesterday.match(regx)[0]:
                            key = 'Yesterday'
                            break
                        default:
                            const date = new Date(extracted)
                            key = `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`
                    }

                    if (!oldArchives.hasOwnProperty(key)) {
                        let archive = {
                            label: key,
                            messages: [message]
                        }

                        if (!conversation.today && key === 'Today') {
                            conversation.today = archive
                        }

                        conversation.archives.unshift(archive)
                        oldArchives[key] = archive.messages
                        return
                    }

                    oldArchives[key].unshift(message)
                })

                scroll && Vue.prototype.$nextTick(scrollToBottom)
            }))
        },
        sendMessage(ctx, {conversation, message}) {
            let {today} = conversation

            if (!today) {
                today = {
                    label: 'Today',
                    messages: []
                }

                conversation.today = today
                conversation.archives.unshift(today)
            }

            const msg = {
                id: null,
                at: null,
                mid: conversation.mid,
                msg: message,
                sent: false
            }

            today.messages.push(msg)

            fetch(window.routes.send, {
                method: 'POST',
                body: objectToFormData({
                    mid: conversation.mid,
                    txt: message,
                    _token: window.csrf
                })
            }).then(response => response.json().then(data => {
                msg.id = data.id
                msg.at = data.at
                msg.sent = true
            }))
        },
        removeConversation(ctx, {account, conversation}) {
            return new Promise((resolve, reject) => {
                fetch(window.routes.addConversation + '/' + conversation.id, {
                    method: 'POST',
                    body: objectToFormData({
                        _method: 'delete',
                        _token: window.csrf
                    })
                }).then(response => {
                    if (response.status === 200) {
                        ctx.commit('removeConversation', {account, conversation})

                        Vue.nextTick(scrollToBottom)
                        return resolve()
                    }

                    reject()
                })
            })
        }
    }
})


let metaLoaded = false

store.watch(({account}) => account, (account, oldAccount) => {
    if (oldAccount && Array.isArray(oldAccount.conversations)) {
        oldAccount.conversations.forEach(unSubscribe)

        oldAccount.conversationSelected = null
        oldAccount.conversations = null
    }

    store.commit('setAccount', account)


    fetch(`${window.routes.getConversations}?id=${account.id}&type=${account.type}`).then(response => response.json().then(conversations => {

        conversations.forEach(setConversationAttrs)

        store.commit('setConversations', {account, conversations})


        const {state} = store
        const {meta} = state

        if (metaLoaded) {
            return
        }

        metaLoaded = true

        if (!(
            meta.hasOwnProperty('target')
            && meta.hasOwnProperty('target-type')
        )) {
            return
        }

        const exists = conversations.some(conversation => {
            const ok = conversation.member.userId.toString() === meta.target && conversation.member.type === meta['target-type']

            if (ok) {
                store.commit('setConversation', {account, conversation})
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

                subscribe(account, conversation)
            })
        }
    }))
})

export default store
