import React from "react";
import ReactDom from "react-dom";
import {
    TestBar,
 } from "./components/index";

const App = () => {
    return(
        <TestBar />
    );
};

ReactDom.render(
    <App />,
    document.getElementById("app")
);