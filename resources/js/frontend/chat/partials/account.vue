<template>
    <div class="account">
        <div class="p-2 info">
            <div>
                <img class="profile-pic mx-auto d-block" :src="account.photo" :alt="account.name">
            </div>
            <p class="text-center mt-3 text-light name">{{account.name}}</p>
        </div>
        <div class="text-center p-2 change-account">
            <a href="#" @click="accountModal = !accountModal">
                <span class="desktop">
                    <i class="fa fa-cog" aria-hidden="true"></i> Change account
                </span>
                <span class="mobile mr-3">
                    <i class="fa fa-cog" aria-hidden="true"></i>
                </span>
            </a>
        </div>

        <b-modal v-model="accountModal" title="Change Account" centered scrollable @ok="changeAccount">
            <b-form-select v-model="accountSelected" :options="options"></b-form-select>
        </b-modal>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        data() {
            const state = this.$store.state

            const options = state.accounts.map(account => {
                return {
                    value: account,
                    text: account.name
                }
            })

            return {
                accountModal: false,
                accountSelected: state.account,
                options
            }
        },

        computed: mapState({
            account: 'account',
            accounts: 'accounts'
        }),

        methods: {
            changeAccount() {
                this.$store.commit('setAccount', this.accountSelected)
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import "../var";

    .account {
        height: 60px;
        display: flex;
        background: #9a9a9a;
        position: sticky;
        top: 0;
        z-index: 2;

        @media screen and (min-width: $md) {
            height: auto;
            display: block;
            position: static;
            background: transparent;
        }
    }

    .info {
        height: inherit;
        z-index: 2;
        display: flex;
        align-items: center;

        @media screen and (min-width: $md) {
            height: 260px;
            width: 100%;
            border-bottom: 1px solid #e0e0e0;
            background: #009688;
            display: block;
        }
    }

    .profile-pic {
        border-radius: 8px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, .2);
        background: #fff;
        padding: 10px;
        width: 50px;

        @media screen and (min-width: $md) {
            width: 150px;
            height: auto;
        }
    }

    .name {
        margin-left: 10px;
        white-space: nowrap;
        @media screen and (min-width: $md) {
            white-space: normal;
            margin-left: 0;
        }
    }

    .change-account {
        margin-left: auto;
        height: inherit;
        display: flex;
        align-items: center;

        @media screen and (min-width: $md) {
            display: block;
            border-bottom: 1px solid #dee2e6;
        }
    }

    .desktop {
        display: none;
        @media screen and (min-width: $md) {
            display: inline;
        }
    }

    .mobile {
        display: inline;
        color: #fff;
        font-size: 1.6rem;
        @media screen and (min-width: $md) {
            display: none;
        }
    }
</style>
