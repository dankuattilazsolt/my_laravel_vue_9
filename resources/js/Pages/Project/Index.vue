<template>
    <div class="flex flex-col">
        <div class="grid grid-cols-12 mb-2">
            <div class="col-span-6 px-4 ml-3 mb-3 sm:px-0">
                <h2 class="text-2xl font-medium leading-6 text-gray-900">Projects</h2>
            </div>
            <div class="col-span-6 text-right">
                <button @click="showModal('createProjectModal')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button" data-modal-toggle="createProjectModal">
                    New Project
                </button>
            </div>
            <create_project></create_project>
        </div>
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="divide-y divide-gray-200 w-full">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <!--                            <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Project</th>-->
                            <th scope="col" class="px-6 py-3 text-right text-sm font-medium text-gray-500 uppercase tracking-wider">Edit</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(item, index) in projects" :key="item.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div v-if="item.active">
                                    <label :for="'toogle_'+item.id" class="flex relative items-center mb-4 cursor-pointer">
                                        <input type="checkbox" @click="setProjectStatus(item.id)" :id="'toogle_'+item.id" class="sr-only" checked>
                                        <div class="w-11 h-6 bg-gray-200 rounded-full border border-gray-200 toggle-bg dark:bg-gray-700 dark:border-gray-600"></div>
                                        <span class="ml-3 text-md font-medium text-gray-900 dark:text-gray-300">
                                                    {{ item.name }}
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"> Active </span>
                                              </span>
                                    </label>
                                </div>
                                <div v-else>
                                    <label :for="'toogle_'+item.id" class="flex relative items-center cursor-pointer">
                                        <input type="checkbox" @click="setProjectStatus(item.id)" :id="'toogle_'+item.id" class="sr-only">
                                        <div class="w-11 h-6 bg-gray-200 rounded-full border border-gray-200 toggle-bg dark:bg-gray-700 dark:border-gray-600"></div>
                                        <span class="ml-3 text-md font-medium text-gray-900 dark:text-gray-300">
                                                <span class="'line-through">{{ item.name }}</span>
                                              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800"> InActive </span>
                                          </span>
                                    </label>
                                    <div class="text-sm">You cannot add new task this project!</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="showModal('editProjectModal', item.id);" class="text-indigo-600 hover:text-indigo-900" type="button" data-modal-toggle="delete_modal">Edit</button> /
                                <button @click="showModal('deleteProjectModal', item.id)" class="text-indigo-600 hover:text-indigo-900" type="button" data-modal-toggle="delete_modal">Remove</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <delete_project></delete_project>
                    <edit_project></edit_project>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import create_project from "./Create.vue";
import delete_project from "./Delete.vue";
import edit_project from "./Edit.vue";
export default {
    data(){
        return {
            projects: {},
            status: {},
            project_id: 0,
        }
    },
    components: {
        create_project,
        delete_project,
        edit_project
    },
    mounted() {
        this.fetchProjects();
        // this.$root.$on('projects', () => {
        //     this.fetchProjects();
        // });
    },
    methods: {
        fetchProjects() {
            axios.get('/fetch/projects').then(response => {
                this.projects = response.data.projects;
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        },
        showModal(key, id){
            this.project_id = id ? id : 0;
            toggleModal(key);
            if(key == "editProjectModal"){
                this.$root.$emit('getProject');
                this.$root.$emit('focus_editProjectModal');
            }
            if(key == "createProjectModal"){
                this.$root.$emit('focus_createProjectModal');
            }
            if(key == "deleteProjectModal"){
                this.$root.$emit('focus_deleteProjectModal');
            }
        },
        setProjectStatus(id){
            axios.post('/setProjectStatus/'+id).then(response => {
                this.fetchProjects();
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        }
    }
}
</script>
