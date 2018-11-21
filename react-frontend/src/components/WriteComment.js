import React from 'react';
import '../assets/css/writecomment.css'

const WriteComment = (props) => {
    return (
        <div>
            <div className="write-comment mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input onChange={props.changeComment} className="mdl-textfield__input" type="text" id="sample3" />
                <label className="mdl-textfield__label" htmlFor="sample3">Comment...</label>
            </div>
            <button onClick={props.addComment} value={props.id} className="button mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored">
            Add comment
            </button>
        </div>
    )
}

export default (WriteComment);