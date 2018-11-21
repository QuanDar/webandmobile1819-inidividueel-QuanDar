import React from 'react';
import { connect } from 'react-redux';
import PropTypes from "prop-types";
import '../assets/css/comment.css'

const Comment = (props) => {
    return (
        <div className="mdl-list__item mdl-list__item--three-line mdl-shadow--2dp background whole">
            <span className="mdl-list__item-primary-content">
                <span className="mdl-chip__contact mdl-color--black mdl-color-text--white">{props.username.substring(0, 1)}</span>
                <span className={"small_text"}>
                    {props.username}
                </span>
                <span className="small_text mdl-list__item-text-body ">
                    {props.content}
                </span>
            </span>
        </div>
    )
};

Comment.propTypes = {
    id: PropTypes.number,
    content: PropTypes.string,
    username: PropTypes.string,
    date: PropTypes.string
};

export default connect()(Comment);