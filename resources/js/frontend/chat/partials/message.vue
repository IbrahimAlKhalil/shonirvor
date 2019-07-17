<template>
    <div :class="classList" @mouseover="showMenu($event)" @mouseout="hideMenu($event)" ref="wrapper">
        <div class="img">
            <img :src="pic" class="profile-pic">
        </div>

        <div class="message">
            <div v-if="item.sent !== false" class="date">{{formatDate(item.at)}}</div>
            <div class="text">
                {{item.msg}}
            </div>
        </div>

        <div v-if="out && (item.sent === false || menu)" class="d-flex align-items-center mt-3 ml-2">
            <i v-if="item.sent === false" class="fa fa-spinner"></i>
            <!--<i v-if="menu" class="fa fa-ellipsis-v"></i>-->
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            item: {
                type: Object,
                required: true
            }
        },

        data() {
            return {
                menu: false
            }
        },

        methods: {
            formatDate(str) {
                const date = new Date(str)

                let hours = date.getHours()
                let minutes = date.getMinutes()
                const ampm = hours >= 12 ? 'pm' : 'am'
                hours = hours % 12
                hours = hours ? hours : 12
                minutes = minutes < 10 ? '0' + minutes : minutes

                return hours + ':' + minutes + ' ' + ampm
            },

            hideMenu(evt) {
                this.menu = false
            },

            showMenu(evt) {
                this.menu = true
            }
        },

        computed: {
            classList() {
                return {
                    wrapper: true,
                    in: !this.out,
                    out: this.out
                }
            },

            out() {
                return this.$store.state.account.conversationSelected.mid === this.item.mid
            },

            pic() {
                return this.out ? this.$store.state.account.photo : this.$store.state.account.conversationSelected.member.photo
            }
        }
    }
</script>

<style lang="scss" scoped>
    $bgIn: #00aeff;
    $bgOut: #e79fff;

    .profile-pic {
        height: 36px;
        border-radius: 50%;
        box-shadow: 0 1px 6px rgba(0, 0, 0, .3);
    }

    .wrapper {
        display: flex;
        padding: 20px;
    }

    .date {
        font-size: .7rem;
    }

    .text {
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 4px rgba(0, 0, 0, .3);
        position: relative;
        word-break: break-word;

        &:after {
            content: '';
            border: 6px solid transparent;
            position: absolute;
            top: 10px;
        }

    }

    .in {
        .img {
            order: 1;
        }

        .message {
            margin-right: 20px;
            margin-left: auto;
        }

        .text {
            background: $bgIn;

            &:after {
                right: -18px;
                transform: rotate(155deg);
                border-right: 15px solid $bgIn;
            }
        }

        .date {
            text-align: right;
        }
    }

    .out {
        .message {
            margin-left: 20px;
        }

        .text {
            background: $bgOut;

            &:after {
                left: -18px;
                border-right: 15px solid $bgOut;
                transform: rotate(20deg);
            }
        }
    }

    @keyframes rotate {
        from {
            transform: rotate(0deg)
        }
        to {
            transform: rotate(360deg)
        }
    }

    .fa-spinner {
        animation: rotate 700ms linear infinite;
    }
</style>
