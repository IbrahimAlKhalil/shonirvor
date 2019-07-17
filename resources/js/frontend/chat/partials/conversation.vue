<template>
    <section id="chat-section" :class="'flex-grow-1 position-relative' + ($store.state.conversation?' active':'')">
        <template v-if="archives">
            <header>
                <button class="btn btn-dark back" @click="$store.commit('deActivateConvLayout')"><i
                        class="fa fa-arrow-left"></i></button>
                <div class="spacer"></div>

                <b-dropdown variant="link" size="lg" no-caret>
                    <template slot="button-content">
                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                    </template>

                    <b-dropdown-item href="#" @click="remove"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                        Conversation
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
        <div v-else-if="archives === false" class="spinner">
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
                if (this.account.conversationSelected === null) {
                    // No conversation selected
                    return null
                }

                return this.account.conversationSelected.archives
            }
        },
        methods: {
            remove() {
                const {state} = this.$store
                const {account} = state
                const conversation = account.conversationSelected

                this.$bvModal.msgBoxConfirm('Please confirm that you want to delete this conversation.', {
                    title: 'Please Confirm',
                    size: 'md',
                    buttonSize: 'sm',
                    okVariant: 'danger',
                    okTitle: 'YES',
                    cancelTitle: 'NO',
                    footerClass: 'p-2',
                    hideHeaderClose: false,
                    centered: true
                }).then(value => {
                    if (value) {
                        this.$store.dispatch('removeConversation', {account, conversation}).then(() => {
                            this.$bvToast.toast('The conversation has been successfully deleted', {
                                title: 'Success',
                                autoHideDelay: 5000,
                                variant: 'success'
                            })
                        })
                    }
                })
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
        flex-direction: column;
        justify-content: space-between;
    }

    header {
        height: 40px;
        position: sticky;
        top: 0;
        background: #fff;
        box-shadow: 0 0 5px rgba(0, 0, 0, .1);
        display: flex;
        justify-content: space-between;
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

    .back {
        margin: 3px;
        cursor: pointer;
        display: none;
    }


    @media all and (min-width: $md) {
        section {
            display: flex;
        }

        .spacer {
            display: block;
        }
    }


    @media all and (max-width: $md) {
        .back {
            display: block;
        }

        section.active {
            display: flex !important;
        }
    }
</style>
