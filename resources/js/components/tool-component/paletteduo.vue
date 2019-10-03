<template>
    <div>
        <palette ref="a" style="margin-bottom: 5px;"></palette>
        <palette2 ref="b"></palette2>
    </div>
</template>

<script>
    import palette from './palette';
    import palette2 from './palette';

    export default {
        name: "paletteduo",
        components: {
            palette,
            palette2,
        },
        mounted() {
            let parent = this;
            this.$refs.a.$on(`data`, function (data) {
                console.log(1);
                parent.$emit(`data`, data, undefined);
            });
            this.$refs.b.$on(`data`, function (data) {
                console.log(2);
                parent.$emit(`data`, undefined, data);
            });

            this.$refs.a.params.title = this.params.title;
            this.$refs.b.params.title = this.params.title1;
        },
        computed: {},
        methods: {

        },
        data() {
            return {
                params: { title: '', title1: '' },
            }
        },
    }
</script>

<style scoped lang="scss">

</style>
