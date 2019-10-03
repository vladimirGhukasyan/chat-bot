<template>
    <div ref="typespeed" class="type-speed-tool-component" style="display: flex;">
        <dropdown ref="dropDown" style="height: 82px;"></dropdown>
        <div class="block" v-if="type === 'Standart'">
            <div class="item">
                <div>Delay</div>
                <div>
                    <div>0.5</div>
                    <div>sec</div>
                </div>
            </div>
            <div class="item">
                <div>Message customizable</div>
                <div>
                    <div>Off</div>
                    <div>|</div>
                </div>
            </div>
        </div>
        <div class="block" v-if="type === 'Custom'">
            <div class="item">
                <div>Average human reading speed</div>
                <div>
                    <div>300</div>
                    <div>words / min</div>
                </div>
            </div>
            <div class="item">
                <div>Max Delay</div>
                <div>
                    <div>2.0</div>
                    <div>sec</div>
                </div>
            </div>
            <div class="item">
                <div>Message customizable</div>
                <div>
                    <div>Off</div>
                    <div>|</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import dropdown from '../tool-component/dropdown';

    export default {
        name: "typespeed",
        components: {
            dropdown
        },
        computed: {},
        mounted() {
            let parent = this;

            this.$refs.dropDown.data = ['Standart', 'Custom'];
            this.$refs.dropDown.$on(`data`, function (data) {
                parent.type = data;
                // parent.$emit('data', `${parent.type}:` + (300 + data * 400) + 'px');
            });
        },
        methods: {
            selectItem(id) {

            }
        },
        data() {
            return {
                type: ''
            }
        },
    }
</script>

<style scoped lang="scss">
    .type-speed-tool-component {
        .block {
            background: #fff;
            border-radius: 5px;
            margin-left: 10px;
            width: 220px;

            .item {
                display: flex;
                padding: 10px;
                font-size: 12px;

                div {
                    flex: 1;
                    align-self: center;
                }

                div:last-child {
                    display: flex;
                    background: #23282B;
                    border-radius: 28px;
                    color: #ffffff;
                    align-self: center;
                    padding: 6px;
                    font-size: 10px;
                    height: 15px;
                    flex: 0.6;

                    div {
                        align-self: center;
                    }

                    div:first-child {
                        text-align: center;
                        background: #fff;
                        padding: 2px 5px;
                        border-radius: 10px;
                        color: #000;
                        font-weight: 900;
                    }
                }
            }
        }

        /*padding: 10px;
        background: #fff;
        border-radius: 5px;
        display: flex;
        flex-direction: column;
        margin-right: 5px;
        height: 56px;*/
    }
</style>
