<template>
    <section id="chat-section" class="flex-grow-1 position-relative">
        <template v-if="archives">
            <header>
                <b-dropdown variant="link" size="lg" no-caret>
                    <template slot="button-content">
                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                    </template>

                    <b-dropdown-item href="#"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete Conversation
                    </b-dropdown-item>
                </b-dropdown>
            </header>

            <div class="msgs">
                <div v-for="(date, key) in archives">
                    <div class="date-wrapper">
                        <span class="hr"></span>
                        <span class="date">{{key}}</span>
                        <span class="hr"></span>
                    </div>
                    <transition-group name="slide-fade">
                        <message v-for="(message, index) in date" :key="key + index" :item="message"/>
                    </transition-group>
                </div>
            </div>

            <message-input/>
        </template>
        <p v-else-if="archives === null" class="text-center fa-2x font-weight-bold mt-5">No conversations</p>
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
            archives() {
                if (this.account.conversationSelected === false) {
                    // Not loaded
                    return false
                }

                if (this.account.conversationSelected === null) {
                    // No conversation
                    return null
                }

                if (this.account.conversationSelected.archives === null) {
                    // archives are not loaded
                    return false
                }

                return this.account.conversationSelected.archives
            }
        },
        components: {message, messageInput}
    }
</script>

<style lang="scss" scoped>
    @import "../var";

    section {
        overflow-y: auto;
        overflow-x: hidden;
        display: none;

        @media all and (min-width: $md) {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
    }

    header {
        height: 40px;
        position: sticky;
        top: 0;
        background: #fff;
        box-shadow: 0 0 5px rgba(0, 0, 0, .1);
        display: flex;
        justify-content: flex-end;
        z-index: 3;
    }

    .msgs {
        width: 100%;
        margin-top: auto;
    }

    .date-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px 50px;
        background: #fff;
        position: sticky;
        top: 40px;
        z-index: 2;
    }

    .hr {
        width: 100%;
        border-bottom: 1px dotted #6c757c;
    }

    .date {
        white-space: nowrap;
        padding: 0 15px;
        font-weight: bold;
    }
</style>
