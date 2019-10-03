<template>
    <div ref="align" class="align-tool-component">
        <a @click="setAlign('flex-start')" :class="[selected === 'flex-start' ?'selected' :'']"></a>
        <a @click="setAlign('center')" :class="[selected === 'center' ?'selected' :'']"></a>
        <a @click="setAlign('flex-end')" :class="[selected === 'flex-end' ?'selected' :'']"></a>
    </div>
</template>

<script>
    export default {
        name: "align",
        components: {

        },
        computed: {

        },
        mounted() {

        },
        methods: {
            setAlign(align) {
                this.selected = align;
                this.$emit('data', align);
            }
        },
        data() {
            return {
                selected: 'flex-end'
            }
        },
    }
</script>

<style scoped lang="scss">
    .align-tool-component {
        display: flex;
        padding: 10px;
        background: #fff;
        border-radius: 5px;

        a {
            border: 1px solid #000000;
            cursor: pointer;
            width: 7px;
            height: 7px;
            border-radius: 8px;
            margin-right: 5px;
        }

        .selected {
            background: #000000;
        }

        a:last-child {
            margin-right: 0;
        }
    }
</style>
