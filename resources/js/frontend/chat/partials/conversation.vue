<template>
    <section class="flex-grow-1 position-relative">
        <template v-if="messages">
            <header>
                <b-dropdown variant="link" size="lg" no-caret>
                    <template slot="button-content">
                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                    </template>

                    <b-dropdown-item href="#"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete Conversation
                    </b-dropdown-item>
                </b-dropdown>
            </header>

            <div>
                <message v-for="(message, index) in messages" :key="index">
                    This is a test message sage This is a test message This is a test message This is a test messagesage
                    This is a test message This is a test message This is a test message
                </message>
            </div>

            <message-input/>
        </template>
        <p v-else-if="messages === null" class="text-center fa-2x font-weight-bold mt-5">No conversations</p>
        <div v-else class="spinner">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </section>
</template>

<script>
    import message from './message'
    import messageInput from './message-input'
    import {mapState} from 'vuex'

    export default {
        computed: {
            ...mapState({
                account: 'account'
            }),
            messages() {
                if (this.account.conversationSelected === false) {
                    // Not loaded
                    return false
                }

                if (this.account.conversationSelected === null) {
                    // No conversation
                    return null
                }

                if (this.account.conversationSelected.messages === null) {
                    // Messages are not loaded
                    return false
                }

                return this.account.conversationSelected.messages
            }
        },
        components: {message, messageInput}
    }
</script>

<style lang="scss" scoped>
    @import "../var";

    section {
        overflow-y: auto;
        display: none;

        @media all and (min-width: $md) {
            display: flex;
            flex-wrap: wrap;
        }
    }

    header {
        width: 100%;
        height: 40px;
        position: sticky;
        top: 0;
        background: #fff;
        box-shadow: 0 0 5px rgba(0, 0, 0, .1);
        display: flex;
        justify-content: flex-end;
        z-index: 2;
    }
</style>
