<template>
    <div :class="classList" ref="wrapper">
        <div class="img">
            <img :src="pic" class="profile-pic">
        </div>

        <div class="message">
            <div v-if="item.sent !== false" class="date">{{formatDate(item.at)}}</div>
            <div class="text">
                {{item.msg}}
            </div>
        </div>

        <div v-if="out && item.sent === false" class="d-flex align-items-center mt-3 ml-2">
            <i v-if="item.sent === false" class="fa fa-spinner"></i>
        </div>

        <div v-if="out" class="menu align-items-center mt-3">
            <b-dropdown size="md" variant="link" no-caret lazy>
                <template slot="button-content">
                    <i class="fa fa-ellipsis-v"></i>
                </template>
                <b-dropdown-item @click.prevent="removeMessage"><i class="fa fa-trash"></i> &nbsp;Delete
                </b-dropdown-item>
            </b-dropdown>
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

        methods: {
            formatDate(str) {
                const date = new Date(str);

                let hours = date.getHours();
                let minutes = date.getMinutes();
                const ampm = hours >= 12 ? 'pm' : 'am';
                hours = hours % 12;
                hours = hours ? hours : 12;
                minutes = minutes < 10 ? '0' + minutes : minutes;

                return hours + ':' + minutes + ' ' + ampm;
            },

            removeMessage() {
                const {item} = this.$props;

                fetch(window.routes.getMessages + '/' + item.id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': window.csrf
                    },
                }).then(() => {
                    item.remove();
                });
            }
        },

        computed: {
            classList() {
                return {
                    wrapper: true,
                    in: !this.out,
                    out: this.out
                };
            },

            out() {
                return this.$store.state.account.conversationSelected.mid === this.item.mid;
            },

            pic() {
                return this.out ? this.$store.state.account.photo : this.$store.state.account.conversationSelected.member.photo;
            }
        }
    };
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

        .menu {
            display: none;
            cursor: pointer;
            padding: 10px;
        }

        &:hover {
            .menu {
                display: flex;
            }
        }
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
