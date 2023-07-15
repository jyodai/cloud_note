import NoteSetting from '~/types/models/noteSetting';

export default interface User {
    id: number;
    name: string;
    user_type: number;
    email: string;
    note_setting: NoteSetting;
}
