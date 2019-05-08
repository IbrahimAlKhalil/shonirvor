<template>
    <div :class="classList">
        <div class="img">
            <img :src="profilePic" alt="Ibrahim Al Khalil" class="profile-pic">
        </div>

        <div class="message">
            <div class="date">7 Feb 2019</div>
            <div class="text"><slot/></div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            type: {
                type: String,
                required: false,
                default: 'outgoing'
            }
        },
        data() {
            return {
                profilePic: window.profilePic
            }
        },
        computed: {
            classList() {
                return {
                    wrapper: true,
                    incoming: this.$props.type === 'incoming',
                    outgoing: this.$props.type === 'outgoing'
                };
            }
        }
    }
</script>

<style lang="scss" scoped>
    $bgIncoming: #00aeff;
    $bgOutgoing: #e79fff;

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

        &:after {
            content: '';
            border: 6px solid transparent;
            position: absolute;
            top: 10px;
        }

    }

    .incoming {
        .img {
            order: 1;
        }

        .message {
            margin-right: 20px;
            margin-left: auto;
        }

        .text {
            background: $bgIncoming;

            &:after {
                right: -18px;
                transform: rotate(155deg);
                border-right: 15px solid $bgIncoming;
            }
        }

        .date {
            text-align: right;
        }
    }

    .outgoing {
        .message {
            margin-left: 20px;
        }

        .text {
            background: $bgOutgoing;

            &:after {
                left: -18px;
                border-right: 15px solid $bgOutgoing;
                transform: rotate(20deg);
            }
        }
    }
</style>
