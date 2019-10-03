<template>
    <div ref="slider" class="slider-tool-component" style="display: flex;">
        <a style="">+</a>
        <div class="line"></div>
        <div class="knob"></div>
        <a>-</a>
    </div>
</template>

<script>
    export default {
        name: "slider",
        computed: {

        },
        mounted() {
            let parent = this;
            let node = this.$refs.slider.children[2];
            let topY = 0;

            let isDrag = false;
            node.addEventListener('mousedown', function (e) {
                isDrag = true;
                parent.startY = e.pageY - parent.offsetY;
            });
            document.addEventListener('mouseup', function (e) {
                isDrag = false;
            });

            this.$refs.slider.addEventListener('mousemove', function (e) {
                if (isDrag) {
                    parent.offsetY = e.pageY - parent.startY;
                    topY = parent.offsetY - 54;
                    if (topY < -94) topY = -94;
                    if (topY > -9) topY = -9;
                    node.style.top = topY + 'px';

                    parent.$emit('data', 1 - (topY + 94) / 85, 1 - (topY + 94) / 85);
                }
            });

            this.setValue(this.selected);
        },
        deactivated() {

        },
        methods: {
            setValue(val) {
                val = Number.parseFloat(val);
                if (isNaN(val)) val = 0;
                let node = this.$refs.slider.children[2];
                this.selected = val;
                this.offsetY = (-9 - this.selected * 84) + 54;
                node.style.top = (-9 - this.selected * 84) + 'px';
            }
        },
        data() {
            return {
                startY: 0,
                offsetY: 0,
                selected: 0
            }
        },
    }
</script>

<style scoped lang="scss">
    .slider-tool-component {
        display: flex;
        flex-direction: column;
        background: #fff;
        border-radius: 5px;
        padding: 10px;
        // width: 160px;
        user-select: none;
        -moz-user-select: none;
        width: 21px;

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
            background: rgb(156, 156, 156) none repeat scroll 0 0;
            position: relative;
            top: 7px;
            // left: 10px;
            height: 100px;
            align-self: center;
        }

        .knob {
            background: #4c4c4c none repeat scroll 0 0;
            border-radius: 16px;
            height: 16px;
            width: 5px;
            margin-bottom: -7px;
            position: relative;
            top: -54px;
            // left: 8px;
            cursor: pointer;
            align-self: center;
        }
    }
</style>
