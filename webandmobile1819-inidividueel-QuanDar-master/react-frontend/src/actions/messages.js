import * as types from './actionTypes';
import axios from 'axios';

export function fetchMessages() {
    return function (dispatch) {
        axios.get('http://localhost:8000/messagesWithComments')
            .then((response) => {
                dispatch({type: types.FETCH_MESSAGES, payload: response.data})
            })
            .catch((err) => {
                throw(err)
            })
    }
}

export function fetchCategories() {
    return function (dispatch) {
        axios.get('http://localhost:8000/categories')
            .then((response) => {
                dispatch({type: types.FETCH_CATEGORIES, payload: response.data})
            })
            .catch((err) => {
                throw(err)
            })
    }
}

export function fetchMessageById(id) {
    return function (dispatch) {
        axios.get('http://localhost:8000/messages/'+id)
            .then((response) => {
                dispatch({type: types.FETCH_MESSAGE_BY_ID, payload: response.data})
            })
            .catch((err) => {
                throw(err)
            })
    }
}

export function fetchMessageByContent(query) {
    return function (dispatch) {
        axios.get('http://localhost:8000/messages/content/'+query)
            .then((response) => {
                dispatch({type: types.FETCH_MESSAGES_BY_CONTENT, payload: response.data})
            })
            .catch((err) => {
                throw(err)
            })
    }
}

export function fetchMessageByContentAndCategory(content, category) {
    return function (dispatch) {
        axios.get('http://localhost:8000/messages/content-and-category/'+content+'/'+category)
            .then((response) => {
                dispatch({type: types.FETCH_MESSAGES_BY_CONTENT_AND_CATEGORY, payload: response.data})
            })
            .catch((err) => {
                throw(err)
            })
    }
}

export function deleteMessage(id) {
    return function (dispatch) {
        axios.delete('http://localhost:8000/messages/delete/'+id)
            .then((response) => {
                dispatch({type: types.DELETE_MESSAGE, payload: response.data, id: id})
            })
            .catch((err) => {
                throw(err);
            })
    }
}

export function upvoteMessage(id) {
    return function (dispatch) {
        axios.put('http://localhost:8000/messages/upvote/'+id)
            .then((response) => {
                dispatch({type: types.UPVOTE_MESSAGE, payload: response.data, id: id})
            })
            .catch((err) => {
                throw(err);
            })
    }
}

export function downvoteMessage(id) {
    return function (dispatch) {
        axios.put('http://localhost:8000/messages/downvote/'+id)
            .then((response) => {
                dispatch({type: types.DOWNVOTE_MESSAGE, payload: response.data, id: id})
            })
            .catch((err) => {
                throw(err);
            })
    }
}

export function filterMessagesByContent(search, category) {
    return {
        type: types.FILTER_MESSAGES_BY_CONTENT,
        search: search,
        category: category
    };
}

export function selectCategory(category) {
    return {
        type: types.SELECT_CATEGORY,
        category: category
    };
}


export function postComment(messageId, comment) {
    var bodyFormData = new FormData();
    bodyFormData.set('messageId', messageId);
    bodyFormData.set('comment', comment);
    return function (dispatch) {
        axios.post('http://localhost:8000/messages/comment/post/', bodyFormData)
            .then((response) => {
                dispatch({type: types.POST_COMMENT_BY_MESSAGE_ID, payload: response.data, id: parseInt(messageId)})
            })
            .catch((err) => {
                throw(err)
            })
    }

}