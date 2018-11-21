import * as types from './actionTypes';



export function loginUser(username, password, role = "ROLE_ADMIN") {
    return {
        type: types.LOGIN_USER,
        username: username,
        password: password,
        role: role
    };
}

export function logoutUser() {
    return {
        type: types.LOGOUT_USER,
    };
}