<template>
    <div ref="imgupload" class="imgupload-tool-component">
        <div @click="uploadImage();" style="cursor: pointer; padding: 10px;display: flex;flex-direction: column;">
            <input ref="fileSelect" type='file' style="position: absolute; opacity: 0; z-index: -10;"/>
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" style="align-self: center;">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M1.2 10.2C1.2 10.3591 1.26321 10.5117 1.37574 10.6243C1.48826 10.7368 1.64087 10.8 1.8 10.8L10.2 10.8C10.3591 10.8 10.5117 10.7368 10.6243 10.6243C10.7368 10.5117 10.8 10.3591 10.8 10.2L10.8 7.8C10.8 7.46863 11.0686 7.2 11.4 7.2C11.7314 7.2 12 7.46863 12 7.8L12 10.2C12 10.6774 11.8104 11.1352 11.4728 11.4728C11.1352 11.8104 10.6774 12 10.2 12L1.8 12C1.32261 12 0.864773 11.8104 0.527208 11.4728C0.189642 11.1352 -5.78131e-08 10.6774 -7.86805e-08 10.2L-1.83588e-07 7.8C-1.98073e-07 7.46863 0.268629 7.2 0.6 7.2C0.931371 7.2 1.2 7.46863 1.2 7.8L1.2 10.2Z" fill="black"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M2.57574 4.02446C2.34142 3.79014 2.34142 3.41025 2.57574 3.17593L5.57574 0.175931C5.81005 -0.0583836 6.18995 -0.0583836 6.42427 0.175931L9.42426 3.17593C9.65858 3.41025 9.65858 3.79014 9.42426 4.02446C9.18995 4.25877 8.81005 4.25877 8.57574 4.02446L6 1.44872L3.42427 4.02446C3.18995 4.25877 2.81005 4.25877 2.57574 4.02446Z" fill="black"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M6 8.40039C5.66863 8.40039 5.4 8.13176 5.4 7.80039L5.4 0.60039C5.4 0.269019 5.66863 0.000390041 6 0.000390027C6.33137 0.000390012 6.6 0.269019 6.6 0.60039L6.6 7.80039C6.6 8.13176 6.33137 8.40039 6 8.40039Z" fill="black"/>
            </svg>
            <div style="color: #2b2b2b; font-size: 10px; text-align: center; margin-top: 10px;">upload image</div>
            <img ref="image" :src="selected" alt="" style="width: 100%;margin-top: 10px;">
        </div>
        <slider v-show="!params.hideSlider" ref="slider" v-on:data="changeSize"></slider>
    </div>
</template>

<script>
    import slider from '../tool-component/slider';

    export default {
        name: "imgupload",
        components: {
            slider
        },
        computed: {

        },
        mounted() {
            // e.target.result
            let parent = this;
            this.$refs.fileSelect.addEventListener('change', function () {
                if (!this.files && !this.files[0]) return;

                let formData = new FormData();
                formData.append("image", parent.$refs.fileSelect.files[0]);
                axios.post('/image-upload', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                }).then((response) => {
                    parent.$refs.image.setAttribute('src', response.data.path);
                    parent.$emit('data', response.data.path, undefined);

                    // Wait until image is load
                    parent.$refs.image.onload = function() {
                        if (parent.params.resizeMode === 'width') {
                            parent.$emit('data', undefined, `100% auto`);
                        } else {
                            if (parent.$refs.image.naturalWidth >= parent.$refs.image.naturalHeight) {
                                parent.$emit('data', undefined, `auto 100%`);
                            } else parent.$emit('data', undefined, `100% auto`);
                        }
                    };

                    parent.$emit('data', undefined, undefined, `${parent.$refs.image.naturalWidth } ${parent.$refs.image.naturalHeight}`);
                }).catch((error) => {
                    console.log(error);
                });

                /*if (this.files && this.files[0]) {
                    let reader = new FileReader();

                    reader.onload = function (e) {
                        parent.$refs.image.setAttribute('src', e.target.result);
                        parent.$emit('data', e.target.result, undefined);
                        parent.$refs.slider.setValue(0);

                        // Wait until image is load
                        parent.$refs.image.onload = function() {
                            if (parent.params.resizeMode === 'width') {
                                parent.$emit('data', undefined, `100% auto`);
                            } else {
                                if (parent.$refs.image.naturalWidth >= parent.$refs.image.naturalHeight) {
                                    parent.$emit('data', undefined, `auto 100%`);
                                } else parent.$emit('data', undefined, `100% auto`);
                            }

                            parent.$emit('data', undefined, undefined, `${parent.$refs.image.naturalWidth } ${parent.$refs.image.naturalHeight}`);
                        };
                    };
                    reader.readAsDataURL(this.files[0]);
                }*/
            });

            this.$refs.slider.setValue(this.selected1);
        },
        methods: {
            uploadImage: function () {
                this.$refs.fileSelect.click();
            },
            changeSize(value) {
                if (this.params.resizeMode === 'width') {
                    this.$emit('data', undefined, `${100 +  value * 400}% auto`);
                } else {
                    if (this.$refs.image.naturalWidth >= this.$refs.image.naturalHeight) {
                        this.$emit('data', undefined, `auto ${100 + value * 400}%`);
                    } else this.$emit('data', undefined, `${100 +  value * 400}% auto`);
                }

                // console.log(value);
                // console.log(this.$refs.palette.children[0]);
                // this.$refs.palette.children[0].style.filter = `saturate(${value + 0.5})`;
            }
        },
        data() {
            return {
                params: {},
                selected: '',
                selected1: 0
            }
        },
    }
</script>

<style scoped lang="scss">
    .imgupload-tool-component {
        max-width: 200px;
        display: flex;
        // padding: 10px;
        background: #fff;
        border-radius: 5px;
    }
</style>
