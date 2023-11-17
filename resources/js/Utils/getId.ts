export default (suffix = ''): string =>
    `__DSID__${Math.random().toString().slice(2, 8)}___DS_${suffix}__`