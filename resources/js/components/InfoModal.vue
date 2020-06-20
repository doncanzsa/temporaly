<template>
    <div class="modal fade" id="InfoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">
                    {{title}} <span class="ml-3 font-weight-lighter font-italic">{{theme}}</span>
                    <small style="display: block;">{{ (updated_at) ? moment(updated_at).format('LLL') : null}}</small>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container" v-html="showContent()">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        DataRow: {
            type : [Object, null],
            default: null,
        },
    },
    data() {
        return {
            loading : false,
            title: null,
            theme: null,
            content: null,
            updated_at: null,
            filter: null,
        }
    },
    watch: {
        DataRow: function(val, oldVal) {
            let vm = this;
            if (vm.DataRow && vm.DataRow.id) {
                vm.title = vm.DataRow.subjects.name;
                vm.theme = vm.DataRow.theme;
                vm.content = vm.DataRow.content;
                vm.updated_at = vm.DataRow.updated_at;
                vm.filter = vm.DataRow.filter;
            } else {
                this.clearData();
            }
        }, 
    },
    methods: {
        clearData() {
            let vm = this;
            vm.title = null;
            vm.theme = null;
            vm.content = null;
            vm.updated_at = null;
            vm.filter = null;
        }, 
        showContent() {
            let vm = this;
            if (vm.filter && vm.filter.length>0) {
                let wordArray = this.filter.split(' ');
                let new_content = vm.content;
                wordArray.forEach(word => {
                    const reg = new RegExp(word,'gi');
                    new_content = new_content.replace(reg,'<span class="highlight">' + word + '</span>');
                });
                return new_content;
            } else {
                return vm.content;
            }
            
            // if(texto && texto!="") {
            //     let word = "des";
            //     const reg = new RegExp(word,'gi');
            //     let newText = texto.replace(reg,'<span class="highlight">' + word + '</span>');
            //     return newText;
            // } else {
            //     return newText;
            // }
        }
    },
    mounted() {
        let vm = this;
        $('#InfoModal').off('hidden.bs.modal');
        $('#InfoModal').on('hidden.bs.modal', function (e) {
            vm.clearData();
            vm.$emit('closeInfoModal');
        });
        console.log('InfoModal Mounted');
        // span // style // color // black // higt // light
    }
}
</script>
<style>
.highlight {
    font-weight: bolder;
    /* text-transform: uppercase; */
    background-color: palevioletred;
    text-decoration: underline red !important;
}
</style>