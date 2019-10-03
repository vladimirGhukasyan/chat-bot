<template>
    <div style="display: flex;">
        <div ref="dropdown" class="dropdown-tool-component">
            <a @click.stop="selectItem($event, s)" v-for="s in data" v-html="s" :class="[selected === s ?'selected' :'']"></a>
        </div>
        <div v-show="showScrollBar" ref="slider" class="slider-tool-component" style="display: flex;">
            <div class="knob"></div>
            <div class="line" style="pointer-events: none;"></div>
        </div>
    </div>
</template>

<script>
    import VuePerfectScrollbar from 'vue-perfect-scrollbar'

    export default {
        name: "dropdown",
        components: {
            VuePerfectScrollbar
        },
        computed: {},
        mounted() {
            let node = this.$refs.dropdown;
            let parent = this;
            let scrollPosition = 0;
            let refresh = function() {
                scrollPosition = node.scrollTop / (node.scrollHeight - node.getBoundingClientRect().height);
                parent.$refs.slider.children[0].style.top = (10 + scrollPosition * 175) + 'px';
            };
            let scroll = function(event) {
                if (event.wheelDelta > 0 || event.detail < 0)
                    node.scrollTop -= 10;
                else node.scrollTop += 10;
                refresh();
                parent.offsetY = (10 + scrollPosition * 175) - 7;
                return false;
            };
            refresh();

            node.addEventListener('DOMMouseScroll', scroll);
            node.addEventListener('mousewheel', scroll);

            if (Math.round(node.scrollHeight) > Math.round(node.getBoundingClientRect().height))
                this.showScrollBar = true;

            let isDrag = false;
            let node2 = this.$refs.slider.children[0];
            node2.addEventListener('mousedown', function (e) {
                isDrag = true;
                parent.startY = e.pageY - parent.offsetY;
            });
            document.addEventListener('mouseup', function (e) {
                isDrag = false;
            });

            this.$refs.slider.addEventListener('mousemove', function (e) {
                if (isDrag) {
                    parent.offsetY = e.pageY - parent.startY;
                    parent.topY = parent.offsetY + 7;
                    if (parent.topY < 7) parent.topY = 7;
                    if (parent.topY > 185) parent.topY = 185;
                    node2.style.top = parent.topY + 'px';

                    node.scrollTop = (parent.topY - 7) / 178 * (node.scrollHeight - node.getBoundingClientRect().height);
                    refresh();
                }
            });
        },
        methods: {
            selectItem(e, id) {
                if (e.target.getAttribute('data-data') !== undefined && e.target.getAttribute('data-data') !== null)
                    this.$emit('data', e.target.getAttribute('data-data'));
                else this.$emit('data', id);
                this.selected = id;
            }
        },
        data() {
            return {
                topY: 0,
                startY: 0,
                offsetY: 0,
                showScrollBar: false,
                selected: '',
                data: [1, 2, 3]
            }
        },
    }
</script>

<style scoped lang="scss">
    .dropdown-tool-component {
        padding: 5px 20px 5px 10px;
        background: #fff;
        border-radius: 5px;
        max-height: 200px;
        overflow: hidden;

        a {
            cursor: pointer;
            display: flex;
            padding: 10px 5px;
            font-size: 12px;
            text-decoration: none;
            color: #23282B;
            border-bottom: 1px solid rgba(64, 70, 73, 0.1);
            user-select: none;
            -moz-user-select: none;

            &:hover {
                color: #529E37;
                opacity: 0.8;
            }
        }

        a:last-child {
            border-bottom: 0;
        }

        .selected {
            color: #529E37;
        }
    }

    .slider-tool-component {
        display: flex;
        flex-direction: column;
        // background: #fff;
        // border-radius: 5px;
        // padding: 10px;
        // width: 160px;
        user-select: none;
        -moz-user-select: none;
        width: 21px;
        position: relative;
        left: -20px;
        padding-bottom: 15px;

        a {
            font-size: 20px;
            line-height: 14px;
            height: 13px;
            text-align: center;
            cursor: pointer;
            font-family: Tahoma, sans-serif;
        }

        .line {
            width: 1px;
            background: rgba(35, 40, 43, 0.6);
            position: relative;
            top: 7px;
            // left: 10px;
            height: 100%;
            align-self: center;
            margin-top: -8px;
        }

        .knob {
            background: #000000;
            border-radius: 16px;
            height: 20px;
            width: 4px;
            margin-bottom: -7px;
            position: relative;
            top: 10px;
            // left: 8px;
            cursor: pointer;
            align-self: center;
        }
    }
</style>
