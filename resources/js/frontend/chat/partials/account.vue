<template>
    <div class="account">
        <div class="p-2 info">
            <img class="profile-pic mx-auto d-block" :src="profilePic"
                 alt="">
            <p class="text-center mt-3 text-light name">Ibrahim Al Khalil</p>
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


        <b-modal v-model="accountModal" title="Change Account" centered scrollable>
            <b-form-select v-model="accountSelected" :options="accounts"></b-form-select>
        </b-modal>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                profilePic: window.profilePic,
                accountModal: false,

                accountSelected: null,
                accounts: [
                    {value: null, text: 'Please select an account'},
                    {value: 'a', text: 'This is First option'},
                    {value: 'b', text: 'Selected Option'},
                    {value: {C: '3PO'}, text: 'This is an option with object value'},
                    {value: 'd', text: 'This one is disabled', disabled: true}
                ]
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
        height: 100%;
        border-radius: 50%;
        box-shadow: 0 5px 10px rgba(0, 0, 0, .2);

        @media screen and (min-width: $md) {
            width: 150px;
            height: auto;
        }
    }

    .name {
        margin-left: 10px;
        @media screen and (min-width: $md) {
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
