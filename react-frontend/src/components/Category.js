import React from 'react';
import { connect } from 'react-redux';
import PropTypes from "prop-types";
import '../assets/css/search.css'

const Category = (props) => {
    return (
        <option value={props.title}>{props.title}</option>
    )
};

Comment.propTypes = {
    id: PropTypes.number,
    content: PropTypes.string,
    username: PropTypes.string,
    date: PropTypes.string
};

export default connect()(Category);