<template>
    <div>
        <div id="account" class="p-2">
            <img class="profile-pic mx-auto d-block" :src="profilePic"
                 alt="">
            <p class="text-center mt-3 text-light">Serenity Langworth</p>
        </div>
        <div class="text-center border-bottom p-2">
            <a href="#"><i class="fa fa-cog" aria-hidden="true"></i> Change account</a>
        </div>
    </div>
</template>

<style lang="scss">
    #account {
        background: #717171;
        height: 260px;

        .profile-pic {
            width: 150px;
            border-radius: 50%;
            box-shadow: 0 5px 10px rgba(0, 0, 0, .2);
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
