import * as types from '../actions/actionTypes';

const initialState = {
    loggedIn: false,
    username: '',
    password: '',
    role: ''
};

const user = (state = initialState, action) => {
    switch (action.type) {
        case types.LOGIN_USER:
            return {
                ...state,
                loggedIn: true,
                username: action.username,
                password: action.password,
                role: action.role
            };
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