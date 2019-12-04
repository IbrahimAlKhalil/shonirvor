<template>
    <div class="scroll-wrapper">
        <div class="user-wrapper">
            <ul v-if="conversations" class="list-group mt-2">
                <li v-for="(conversation, index) in conversations" :key="index"
                    :class="`list-group-item list-group-item-action user d-flex align-items-center ${conversation.active?'active':''}`"
                    @click="activeConversation(conversation)">
                    <div class="position-relative">
                        <img :src="conversation.member.photo" :alt="conversation.member.name">
                        <span :class="`status ${conversation.member.online?'online':''}`"></span>
                    </div>
                    <span class="mr-auto ml-2">{{conversation.member.name}}</span>
                    <span class="badge badge-secondary badge-pill"></span>
                </li>
            </ul>
            <div v-else class="spinner absolute">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            conversations() {
                return this.$store.state.account.conversations
            }
        },

        methods: {
            activeConversation(conversation) {
                const {$store} = this
                const {state} = $store

                if (state.account.conversationSelected === conversation) {
                    return
                }

                if (state.account.conversationSelected) {
                    $store.commit('deActivateConversation', state.account.conversationSelected)
                }

                $store.commit('setConversation', {account: state.account, conversation})

                if (!(conversation.archives && conversation.archives.length)) {
                    $store.dispatch('loadMessages', {account: state.account, conversation, scroll: true})
                }

                this.$nextTick(this.$scrollToBottom)
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import "../var";

    .user-wrapper {
        height: 100%;
        position: relative;

        @media screen and (min-width: $md) {
            margin-top: 45px;

            &::-webkit-scrollbar {
                display: none;
            }

            &:hover {
                &::-webkit-scrollbar {
                    display: block;
                }
            }
        }

        .list-group {
            margin: 0 !important;
        }

        .user {
            cursor: pointer;

            img {
                height: 25px;
                border: 2px solid #3c7cfe;
                border-radius: 2px;
            }

            &.active img {
                background: #fff;
            }
        }
    }

    .status {
        padding: 5px;
        position: absolute;
        border-radius: 50%;
        top: -6px;
        left: -6px;
        border: 2px solid #3c7cfe;
        background: #ff7303;

        &.online {
            background: #00ff64;
        }
    }


    @media screen and (min-width: $md) {
        .scroll-wrapper {
            height: calc(100% - 302px);
        }

        .user-wrapper {
            overflow-y: auto;
            margin-top: 0;
        }
    }
</style>
