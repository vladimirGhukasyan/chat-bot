<template>
    <div style="display: flex;">
        <div class="gradient-tool-component">
            <button @click="side = 'up'">UP</button>
            <div>&nbsp;</div>
            <button @click="side = 'down'">DOWN</button>
        </div>
        <palette ref="palette"></palette>
    </div>
</template>

<script>
    import palette from '../tool-component/palette';

    export default {
        name: "gradient",
        components: {
            palette
        },
        mounted() {
            let parent = this;
            this.$refs.palette.$on('data', function (data) {
                if (parent.side === 'up') parent.$emit(`data`, data, undefined);
                else parent.$emit(`data`, undefined, data);
            });
        },
        computed: {},
        methods: {

        },
        data() {
            return {
                side: 'up'
            }
        },
    }
</script>

<style scoped lang="scss">
    .gradient-tool-component {
        background: #ffffff;
        border-radius: 6px;
        display: flex;
        flex-direction: column;
        margin-right: 5px;
        padding: 10px;

        button:first-child {
            background: #D2FFD6;
            border: 0.3px solid rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            border-radius: 5px;
        }

        button {
            cursor: pointer;
            background: #478F4A;
            border: 0.3px solid rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            border-radius: 5px;
            width: 64px;
            height: 64px;
        }
    }
</style>
