<template>
    <main class="d-flex">
        <aside>
            <account/>
            <users/>
        </aside>
        <conversation/>
    </main>
</template>

<script>
    import account from './partials/account';
    import users from './partials/users';
    import conversation from './partials/conversation';

    export default {
        components: {account, users, conversation}
    }
</script>

<style lang="scss">
    @import "var";

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
</style>
