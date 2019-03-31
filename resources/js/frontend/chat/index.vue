<template>
    <main class="d-flex">
        <aside>
            <account/>
            <users/>
        </aside>

        <section>
            <header>

            </header>

            <div>

            </div>
        </section>
    </main>
</template>

<style lang="scss">
    main {
        height: 100vh;
    }

    aside {
        width: 330px;
        height: 100vh;
        background: #fff;
        box-shadow: 0 0 2px rgba(0, 0, 0, .1);
        overflow: hidden;

        > * {
            width: 100%;
        }
    }
</style>

<script>
    import account from './partials/account';
    import users from './partials/users';

    export default {
        components: {account, users}
    }
</script>
