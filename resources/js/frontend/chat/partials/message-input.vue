<template>
    <div class="wrapper">
        <transition name="jump">
            <transition-group v-if="images.length" class="images d-flex flex-wrap" name="jump" tag="div">
                <div v-for="(image, index) in images" class="img" :key="index+'k'">
                    <img class="img-thumbnail" :src="image">
                    <span class="cross fa-times fa" @click="removeImage(index)"></span>
                </div>
            </transition-group>
        </transition>
        <div class="d-flex message-box">
            <label class="btn btn-secondary input-image">
                <input type="file" accept="image/*" class="d-none" multiple @input="showImages($event)">
                <i class="fa fa-picture-o" aria-hidden="true"></i>
            </label>
            <textarea v-model="message" class="form-control" rows="2" placeholder="এখানে লিখুন..."
                      @keydown.enter.prevent.exact="send" @focus="typing(true)" @blur="typing(false)"></textarea>
            <i class="fa fa-paper-plane send-icon" aria-hidden="true" @click="send"></i>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                images: [],
                message: ''
            }
        },
        methods: {
            showImages(event) {
                this.images = []

                for (let image of event.target.files) {
                    new Promise(resolve => {
                        this.images.push(URL.createObjectURL(image))
                        resolve()
                    })
                }
            },

            removeImage(index) {
                this.images.splice(index, 1)
            },

            send() {
                const {$store} = this
                const {state} = $store

                $store.dispatch('sendMessage', {
                    conversation: state.account.conversationSelected,
                    message: this.message
                })

                this.message = ''

                this.$nextTick(this.$strollToBottom)
            },

            typing(typing) {
                this.$store.state.account.conversationSelected.echo.whisper(typing ? 'typing' : 'typing-stopped', {})
            }
        }
    }
</script>

<style lang="scss" scoped>
    .wrapper {
        position: sticky;
        bottom: 10px;
        z-index: 2;
    }

    .message-box {
        margin: 10px;
        box-shadow: 0 0 15px 8px rgba(0, 0, 0, .1);
        border-radius: 5px;
        overflow: hidden;
        position: relative;
    }

    textarea {
        resize: none;
        border-radius: 0;
        border: 0;
    }

    .send-icon {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.7rem;
        color: #607D8B;
        cursor: pointer;
    }

    .input-image {
        margin-bottom: 0;
        width: 60px;
        line-height: 3;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        cursor: pointer;
    }

    .images {
        margin: 10px;
        background: #eee;
        box-shadow: 0 0 4px rgba(0, 0, 0, .2);
        border-radius: 5px;
        padding: 5px;

        .img {
            margin: 5px;
            position: relative;
        }

        img {
            height: 100px;
        }

        .cross {
            position: absolute;
            right: -5px;
            top: -5px;
            background: #F44336;
            border-radius: 30px;
            height: 20px;
            width: 20px;
            text-align: center;
            line-height: 1.2;
            color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, .2);
            cursor: pointer;
        }
    }
</style>
