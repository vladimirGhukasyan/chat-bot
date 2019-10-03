<template>
    <div ref="resize" class="resize-tool-component" style="display: flex;">
        <div class="resize-select-tool-component">
            <a @click="selectItem('height')" style="margin-bottom: 5px;" :class="[type === 'height' ?'selected' :'']">
                <img src="/assets/images/design/height.png" style="width: 24px;">
            </a>
            <a @click="selectItem('width')" :class="[type === 'width' ?'selected' :'']">
                <img src="/assets/images/design/width.png" style="width: 24px;">
            </a>
        </div>
        <slider ref="slider"></slider>
    </div>
</template>

<script>
    import dropdown from '../tool-component/dropdown';
    import slider from '../tool-component/slider';

    export default {
        name: "resize",
        components: {
            dropdown,
            slider
        },
        computed: {

        },
        mounted() {
            let parent = this;

            this.$refs.slider.$on(`data`, function (data) {
                if (parent.type === 'width') {
                    parent.currentWidth = data * 1;
                    parent.$emit('data', data, undefined);
                }
                else {
                    parent.currentHeight = data * 1;
                    parent.$emit('data', undefined, data);
                }
            });

            this.currentWidth = this.selected;
            this.currentHeight = this.selected1;
            this.$refs.slider.setValue(this.selected1);
        },
        methods: {
            selectItem(type) {
                this.type = type;
                if (type === 'width') this.$refs.slider.setValue(this.currentWidth);
                else this.$refs.slider.setValue(this.currentHeight);
            }
        },
        data() {
            return {
                currentWidth: 0,
                currentHeight: 0,
                selected: 0,
                selected1: 0,
                type: `height`,
                data: []
            }
        },
    }
</script>

<style scoped lang="scss">
    .resize-select-tool-component {
        padding: 10px;
        background: #fff;
        border-radius: 5px;
        display: flex;
        flex-direction: column;
        margin-right: 5px;
        height: 56px;

        a {
            cursor: pointer;

            &:hover {
                opacity: 0.8;
            }

            img {
                filter: saturate(0) brightness(0);
            }
        }

        .selected {
            img {
                filter: saturate(1) brightness(1);
            }
        }
    }
</style>
