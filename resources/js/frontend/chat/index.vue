<template>
    <main class="d-flex">
        <template v-if="$store.state.account">
            <aside>
                <account/>
                <users/>
            </aside>
            <conversation/>
        </template>
        <div v-else class="spinner">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </main>
</template>

<script>
    import account from './partials/account'
    import users from './partials/users'
    import conversation from './partials/conversation'

    export default {
        components: {account, users, conversation}
    }
</script>

<style lang="scss">
    @import "var";

    $duration: 150ms;
    .jump-enter-active, .jump-leave-active {
        transition: opacity $duration, transform $duration;
    }

    .jump-enter, .jump-leave-to {
        opacity: 0;
        transform: scale(.9);
    }

    main {
        height: 100vh;
    }

    aside {
        width: 100%;
        height: 100vh;
        background: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, .1);

        @media screen and (min-width: $md) {
            width: 330px;
            overflow: hidden;
        }

        > * {
            width: 100%;
        }
    }

    .spinner {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }
</style>
