<template>
    <div class='logger'>
        <h1><strong> WordPress Debug File Viewer </strong></h1>
        <button @click="handleFileAdd"> Add Log File </button>
    </div>
    <div class="w3-sidebar w3-light-grey w3-bar-block" style="width:25%">
        <li v-for="log in state.logs" class="w3-bar-item w3-button" @click="openLog(log.id)">
            {{log.file_path}}
            <span class="log-monitoring" v-if="state.monitoring === log.file_path">Monitoring...</span>
            <div class="log-btn-grp">
                <button class="log-remover" @click="deleteLog(log.id)">Remove</button>
                <button class="log-clear" @click="clearLogFile(log.file_path)">Clear</button>
                <button class="log-monitor" @click="activeMonitor(log.file_path, log.id)">Monitor</button>
            </div>
        </li>
    </div>

<!-- Page Content -->
    <div style="margin-left:25%">
        <div class="w3-container" v-if="state.current_log.length">
            {{ state.current_log }}
        </div>

        <div class="w3-container" v-else>
            <strong>No log selected.</strong>
        </div>
    </div>
</template>

<script>
    import { reactive, ref, onMounted } from "vue";
    import {useRouter} from 'vue-router';

    export default {
    setup() {
        const errors = ref()
        const router = useRouter();
        const state = reactive({
            logs: {},
            current_log: '',
            current_log_id: false,
            monitoring: localStorage.getItem('log_file_path')
        });

        const handleFileAdd = async () => {
            try {
                const request = await axios.get('/api/open')
                if(request.status === 200 && request.data ) {
                    fetchLogs();
                }
            } catch (e) {
                if(e && e.response.data && e.response.data.errors) {
                    errors.value = Object.values(e.response.data.errors)
                } else {
                    errors.value = e.response.data.message || ""
                }
            }
        }


        const fetchLogs = async () => {
            const result = await axios.get('/api/fetch-logs')
                if (result.status === 200 && result.data) {
                    state.logs = result.data;
                    return true;
                }
        }

        const openLog = async (id, event='onOpen') => {
            const result = await axios.get('/api/fetch-logs/' + id)
                if (result.status === 200 && result.data) {
                    state.current_log = result.data;
                    if (event === 'onClear') state.current_log = ''
                    return true;
                }
        }

        const deleteLog = async (id) => {
            const result = await axios.delete('/api/delete-log/' + id)
                if (result.status === 200) {
                    fetchLogs();
                    return true;
                }
        }

        const clearLogFile = async (file_path) => {
            const result = await axios.post('/api/clear-log/', {
                'file_path': file_path
            })

            if (result.status === 200) {
                openLog(state.current_log_id, 'onClear')
            }
        }

        const activeMonitor = (file_path, id) => {
            state.monitoring = localStorage.setItem('log_file_path', file_path);
            state.current_log_id = id;
        }


        onMounted(() => {
            fetchLogs();
        });

        return {
            errors,
            fetchLogs,
            handleFileAdd,
            state,
            openLog,
            deleteLog,
            clearLogFile,
            activeMonitor
        }
    }
}
</script>
