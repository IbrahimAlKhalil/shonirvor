<template>
    <div class="wrapper">
        <div>
            <img :src="profilePic" alt="Ibrahim Al Khalil" class="profile-pic">
        </div>

        <div class="message">
            <div class="date">7 Feb 2019</div>
            <div class="text">
                This is a test message
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                profilePic: window.profilePic
            }
        }
    }
</script>

<style lang="scss" scoped>
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

    .message {
        margin-left: 20px;
    }

    .text {
        padding: 10px;
        background: #00BCD4;
        border-radius: 10px;
        box-shadow: 0 0 4px rgba(0, 0, 0, .3);
        position: relative;

        &:after {
            content: '';
            border: 6px solid transparent;
            border-right: 15px solid #52bcd3;
            position: absolute;
            top: 10px;
            left: -18px;
            transform: rotate(20deg);
        }

    }
</style>
