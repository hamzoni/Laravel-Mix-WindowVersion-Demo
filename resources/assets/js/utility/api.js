import axios from 'axios'
class API {
    constructor() {
        this.xhr = axios.create();
    }

    uploadFile(formData) {
        return new Promise((resolve, reject) => {
            this.xhr.post('files/upload', formData)
            .then(response => resolve(response))
            .catch(error => reject(error));
        })
    }

    saveXml(xmls) {
        return new Promise((resolve, reject) => {
            let params = {
                xmls: xmls
            }
            this.xhr.post('files/save', params)
            .then(response => resolve(response))
            .catch(error => reject(error))
        })
    }

    listFiles() {
        return new Promise((resolve, reject) => {
            this.xhr.post('files/get')
            .then(response => resolve(response))
            .catch(error => reject(error))
        })
    }

    listLines(filename) {
        return new Promise((resolve, reject) => {
            let params = {
                fn: filename
            }
            this.xhr.post('lines/get', params)
            .then(response => resolve(response))
            .catch(error => reject(error));
        })
    }
}

export default API;
