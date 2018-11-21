import * as types from '../actions/actionTypes';

const initialState = {
    loggedIn: false,
    username: '',
    password: '',
    role: '',
    users: [
        {username: 'root', password: 'root', role: 'ROLE_ADMIN'},
        {username: 'megaroot', password: 'root', role: 'ROLE_ADMIN'},
        {username: 'roy', password: 'pxl', role: 'ROLE_USER'},
    ]
};

const user = (state = initialState, action) => {
    switch (action.type) {
        case types.LOGIN_USER:
            let valid = false;
            initialState.users.map(user => {
                if((user.username === action.username) && (user.password === action.password)){
                    valid = true;
                }
                return true;
            });
            if(valid){
                return {
                    ...state,
                    loggedIn: true,
                    username: action.username,
                    password: action.password,
                    role: action.role
                };
            }
            else{
                return {
                    ...state,
                    loggedIn: false
                }
            }
        case types.LOGOUT_USER:
            return {
                ...state,
                loggedIn: false,
                username: '',
                password: '',
                role: ''
            };
        default:
            return state
    }
};

export default user