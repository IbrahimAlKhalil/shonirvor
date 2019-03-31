<template>
    <div class="scroll-wrapper">
        <div class="user-wrapper">
            <ul class="list-group mt-2">
                <li class="list-group-item list-group-item-action user" v-for="i in 20">
                    <img :src="profilePic" alt="">
                    <span class="mr-auto ml-2">Cras justo odio</span>
                    <span class="badge badge-primary badge-pill">{{Math.floor(Math.random() * i)}}</span>
                </li>
            </ul>
        </div>
    </div>
</template>

<style lang="scss">
    .scroll-wrapper {
        height: calc(100% - 302px);
    }

    .user-wrapper {
        height: 100%;
        overflow-y: auto;

        .list-group {
            margin: 0 !important;
        }

        &:hover {
            &::-webkit-scrollbar-thumb {
                background: #3c7bff;
            }
        }

        .user {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;

            img {
                height: 40px;
                border-radius: 50%;
            }
        }
    }
</style>

<script>
    export default {
        data() {
            return {
                profilePic: window.profilePic
            }
        }
    }
</script>
