<template>
    <div id="editProjectModal" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-4 z-50 justify-center items-center h-modal md:h-full md:inset-0">
        <div class="relative px-4 w-full max-w-2xl h-full md:h-auto">

            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">

                <div class="flex justify-between items-start p-4 rounded-t border-b dark:border-gray-600">
                    <h3 class="text-md font-semibold text-gray-900 lg:text-xl dark:text-white">
                        Edit project
                    </h3>
                    <button @click="hideModal('editProjectModal')" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="editProjectModal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>

                <div class="p-6">
                    <form @submit.prevent="updateProject" id="editProjectForm">
                        <div class="grid">
                            <div v-if="success" class="col-span-3 col-end-4 sm:col-span-3">
                                <div class="font-medium text-sm text-green-600">
                                    Updated project!
                                </div>
                            </div>
                            <div class="col-span-3 col-end-4 sm:col-span-3">
                                <label for="name" class="block text-sm font-medium text-gray-700">Project name *</label>
                                <input type="text" name="name" id="name" v-model="fields.name" placeholder="Project name" autocomplete="off" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <div v-if="errors && errors.name" class="text-red-600">{{ errors.name[0] }}</div>
                            </div>
                            <div class="col-span-3 col-end-4 sm:col-span-3 mt-4">
                                <label for="note" class="block text-sm font-medium text-gray-700">Note</label>
                                <textarea
                                    class="form-control block w-full px-3
                                            py-1.5 text-sm text-gray-700
                                            bg-white bg-clip-padding
                                            border border-solid border-gray-300
                                            rounded transition ease-in-out m-0
                                            focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                    id="note"
                                    v-model="fields.note"
                                    rows="3"
                                    placeholder="Note"
                                ></textarea>
                                <div v-if="errors && errors.note" class="text-red-600">{{ errors.note[0] }}</div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="flex items-center p-4 sm:text-right space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                    <button type="submit" ref="editProjectModal" form="editProjectForm" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
                    <button @click="hideModal('editProjectModal')" data-modal-toggle="editProjectModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">Decline</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            fields: {
                name : '',
                note:'',
            },
            errors: {},
            success: false,
            loaded: true,
        }
    },
    mounted() {
        // this.$root.$on('getProject', () => {
        //     this.getProject();
        // });
        // this.$root.$on('focus_editProjectModal', () => {
        //     this.focusInput();
        // });
    },
    computed: {
        name() {
            return this.fields.name;
        },
        note() {
            return this.fields.note;
        }
    },
    methods: {
        focusInput() {
            this.$refs.editProjectModal.focus();
        },
        updateProject() {
            if (this.loaded) {
                this.loaded = false;
                this.success = false;
                this.errors = {};
                axios.put('/projects/'+this.$parent.project_id, this.fields).then(response => {
                    this.loaded = true;
                    this.success = true;
                    this.$root.$emit('projects');
                    this.hideModal('editProjectModal');
                }).catch(error => {
                    this.loaded = true;
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            }
        },
        hideModal(key){
            toggleModal(key, false);
        },
        getProject(){
            axios.get('projects/'+this.$parent.project_id).then(response => {
                this.fields.name = response.data.project.name;
                this.fields.note = response.data.project.note;
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        }
    }
}
</script>
