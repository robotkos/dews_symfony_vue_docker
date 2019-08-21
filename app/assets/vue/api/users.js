import axios from 'axios';

export default {
    create (message) {
        return axios.post(
            '/api/users/create',
            {
                message: message,
            }
        );
    },
    getAll () {
        return axios.get('/api/users/get_all');
    },
}
