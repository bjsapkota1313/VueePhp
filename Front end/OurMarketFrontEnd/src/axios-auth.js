import axios from "axios";
const instance = axios.create({
    baseURL:"https://ourmarketbackend.000webhostapp.com/api/"});
export default instance;
