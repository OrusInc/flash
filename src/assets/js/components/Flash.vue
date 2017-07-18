<template>
    <transition name="flash">
        <div class="flash-container" v-show="showing">
            <transition-group name="notify" tag="div">
                <div 
                    v-for="(alert, key, index) in heap" 
                    class="flash" 
                    :class="'flash-'+alert.level" 
                    v-bind:key="alert"
                >
                    <span 
                        class="flash-close" 
                        v-if="alert.important" 
                        @click.prevent="close(key)"
                    >
                        &times;
                    </span>
                    <div class="flash-left">
                        <span class="flash-icon" v-if="alert.icon">
                            <i class="fa fa-bell" aria-hidden="true"></i>
                        </span>
                        <i 
                        class="fa fa-2x" 
                        :class="alert.options.icon"
                        aria-hidden="true"
                        ></i>
                    </div>
                    <div class="flash-right">
                        <h1 class="flash-title" v-if="alert.title" v-html="alert.title"></h1>
                        <p class="flash-body" v-html="alert.message"></p>
                    </div>
                </div>
            </transition-group>
        </div>
    </transition>
</template>

<script>
    export default {
        props: {
            'alerts': {default: []},
            'icon': {default: "fa-bell-o"},
            'link': {default: null}
        },
        data() {
            return {
                heap: {},
                data: [],
                options: {}
            }
        },
        mounted() {
            this.flash(
                this.sanitize(this.data)
            );
        },
        created() {
            this.init();
            this.data = this.alerts;
            this.options = {
                icon: this.icon,
                link: this.link
            };

            window.events.$on("flash", (data) => {
                this.flash(this.sanitize(data));
            });
        },
        computed: {
            showing() {
                return Object.keys(this.heap).length > 0;
            }
        },
        methods: {
            init() {
                window.events = window.events || new Vue();

                window.flash = (message, title = null, level = "default", important = true, options = {}) => {
                  window.events.$emit("flash", {message, title, level, important, options});
                }
            },
            flash(messages) {
                if (messages.length <= 0) return;

                let alert = messages.shift();
                let key = this.generateKey();

                alert.options = Object.assign({}, this.options, alert.options);
                Vue.set(this.heap, key, alert);

                if (! alert.important) {
                    setTimeout(() => {this.hide(key);}, 2500);
                }
                
                setTimeout(() => this.flash(messages), 1500);
            },

            sanitize(data) {
                if (Array.isArray(data)) return data;

                if (data instanceof String || typeof data === "string" ) {
                    return [
                        {
                            title: null,
                            message: data,
                            level: 'info',
                            important: false,
                            options: {}
                        }
                    ];
                }

                if (typeof data === "object" || data instanceof Object) {
                    return [data];
                }

                return [];
            },

            hide(key) {
                Vue.delete(this.heap, key);
            },

            generateKey() {
                let key = "flash-"+Math.ceil(Math.random() * 1000);
                while(this.heap[key]) {
                    key = "flash-"+Math.ceil(Math.random() * 1000);
                }

                return key;
            },
            close(key) {
                this.hide(key);
            }
        }
    }
</script>

<style>
    .flash-container {
        position: fixed;
        top: 50px;
        right: 40px;
        z-index: 99999;
        padding: 20px 20px;
        width: auto;
        max-height: 80%;
        overflow-y: hidden;
    }
    .flash-container > div {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-end;
    }
    .flash-container .flash {
        position: relative;
        display: flex;
        padding: 10px;
        margin-bottom: 2px;
        min-height: 60px;
        max-height: 110px;
        overflow: hidden;
    }
    .flash-container .flash .flash-close {
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
        right: 14px;
        top: 14px;
        padding: 2px 6px;
        color: #e1e1e1;
        border: 1px solid #e1e1e1;
        border-radius: 50%;
        font-size: 2rem;
        line-height: 1.7rem;
        cursor: pointer;
    }
    .flash-container .flash .flash-close:hover {
        color: #999;
        border-color: #999;
    }
    .flash-container .flash .flash-left {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        color: #444;
        background: #f6f6f6;
        width: 100px;
        max-width: 80px;
        box-shadow: 0 0 3px rgba(10, 10, 10, .185);
    }
    .flash-container .flash .flash-right {
        background: white;
        padding: 16px 22px 16px 10px;
        width: auto;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 150px;
        max-width: 435px;
        background: #fff;
        box-shadow: 0 0 3px rgba(10, 10, 10, .185);
    }
    .flash-container .flash-title {
        margin: 0;
        margin-bottom: 10px;
        font-size: 15px;
        font-weight: 700;
        color: #000 !important;
    }
    .flash-container .flash-body {
        margin: 0;
        font-size: 12px;
    }
    .flash.flash-info .flash-left {
        background-color: #1E90FF !important;
        color: #fff !important;
    }
    .flash.flash-default .flash-left {
        background-color: #f6f6f6 !important;
        color: #444 !important;
    }
    .flash.flash-black .flash-left {
        background-color: #444 !important;
        color: #fff !important;
    }
    .flash.flash-warning .flash-left {
        background-color: #FAFA47 !important;
        color: #444 !important;
    }
    .flash.flash-danger .flash-left {
        background-color: #F71919 !important;
        color: #fff !important;
    }
    .flash.flash-success .flash-left {
        background-color: #32CD32 !important;
        color: #fff !important;
    }
    .notify-enter-active, .notify-leave-active {
      transition: all 1s;
    }
    .notify-enter {
      opacity: 0;
      transform: translateY(30px);
    }
    .notify-leave-to {
      opacity: 0;
      transform: translateY(-30px);
    }
    .flash-enter-active {
      transition: all .2s;
    }
    .flash-leave-active {
      transition: all 2s;
    }
    .flash-enter {
      opacity: 0;
    }
    .flash-leave-to {
      opacity: 0;
    }
</style>
