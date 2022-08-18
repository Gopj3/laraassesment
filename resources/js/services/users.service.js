   export const getUsersAsync = async (page = 1) => {
        try {
            const {data} = await axios.get('/api/users', {params: {page}});

            return data;
        }catch(err) {
            console.error(data);
        }
    }

    export const getTrashedListAsync = async (page = 1) => {
        try {
            const {data} = await axios.get('/api/users/trashed/list', {params: {page}});

            return data;
        }catch(err) {
            console.error(data);
        }
    }

    export const getSingleUserAsync =  async (userId) => {
        try {
            const {data} = await axios.get(`/api/users/${userId}`);
            console.log(data);
            return data;
        }catch(err) {
            console.error(data);
        }
    }

    export const updateSingleUserAsync = async (userId, formData) => {
        try {
            const {data} = await axios.post(`/api/users/${userId}`, formData);

            return data;
        }catch(err) {
            console.error(err);
        }
    }

    export const createUserAsync = async (formData) => {
       try {
           const {data} = await axios.post(`/api/users`, formData);

           return data;
       }catch(err) {
           console.error(err);
       }
    }

    export const softDeleteAsync = async (userId) => {
        try {
            const {data} = await axios.delete(`/api/users/${userId}/delete`);

            return data;
        } catch(err) {
            console.error(err);
        }
    }

    export const restoreAsync = async (userId) => {
        try {
            const {data} = await axios.patch(`/api/users/${userId}/restore`);

            return data;
        } catch(err) {
            console.error(err);
        }
    }

