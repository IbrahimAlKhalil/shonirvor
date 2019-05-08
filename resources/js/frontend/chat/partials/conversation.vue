<template>
    <section class="flex-grow-1">
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
            <message>
                This is a test message sage This is a test message This is a test message This is a test messagesage This is a test message This is a test message This is a test message
            </message>
            <message type="incoming">
                This is a test message This is a test mesThis is a test message
            </message>

            <message type="incoming">
                This is a test a test mesThis is a test message message This ismessage This is
            </message>

            <message>
                This is a test message sage This is a test message This is a test message This is a test messagesage This is a test message This is a test message This is a test message
            </message>

            <message type="incoming">
                This is a test a test mesThis is a test message message This ismessage This is
            </message>

            <message>
                This is a test message sage This is a test message This is a test message This is a test messagesage This is a test message This is a test message This is a test message
            </message>
        </div>

        <message-input/>
    </section>
</template>

<script>
    import message from './message';
    import messageInput from './message-input';

    export default {
        components: {message, messageInput}
    }
</script>

<style lang="scss" scoped>
    @import "../var";

    section {
        overflow-y: auto;
        display: none;

        @media all and (min-width: $md) {
            display: block;
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
        z-index: 2;
    }
</style>
