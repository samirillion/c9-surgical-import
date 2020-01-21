function getActionByName(actionName, actions) {
    return actions.find(action => action.id === actionName);
}

export { getActionByName }