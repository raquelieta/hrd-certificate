import { useToast } from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";
import moment from "moment";

export default function helpers() {
    const assign = (data) => {
        return JSON.parse(JSON.stringify(data));
    };

    const toast = useToast({
        timeout: 1500
    });

    const apply_disabled = (apply) => {
        return apply ? 'opacity-50 cursor-not-allowed': ''
    }

    const val_exists = (arr, data) => {
        return arr.some(function(el) {
            return el[data.key] == data.value;
        }); 
    }

    const date_string = (date) => {let newDate = new Date(date);return newDate.toDateString();}

    // const limitChar = () => {
        
    // }

    return {
        assign,
        toast,
        moment,
        apply_disabled,
        val_exists,
        date_string
    }
}