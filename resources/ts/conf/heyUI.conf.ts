 const heyUIconf = {
    dict: {
        keyName: 'key',
        titleName: 'title',
        dicts: {}
    },
    tree: {
        configs: {},
        default: {
            titleName: 'title',
            keyName: 'key',
            parentName: 'parent',
            childrenName: 'children'
        }
    },
    category: {
        configs: {},
        default: {
            titleName: 'title',
            keyName: 'key',
            parentName: 'parent',
            childrenName: 'children'
        }
    },
    categoryPicker: {
        configs: {},
        default: {
            titleName: 'title',
            keyName: 'key',
            parentName: 'parent',
            childrenName: 'children'
        }
    },
    cascader: {
        configs: {},
        default: {
            titleName: 'title',
            keyName: 'key',
            parentName: 'parent',
            childrenName: 'children'
        }
    },
    uploader: {
        urlName: 'url',
        fileName: 'name',
        thumbUrl(url) {
            return url;
        }
    },
    menu: {
        titleName: 'title',
        keyName: 'key',
        childrenName: 'children'
    },
    autocomplete: {
        configs: {},
        default: {
            maxLength: 20,
            delay: 100,
            loadData: null,
            minWord: 0,
            titleName: 'title',
            keyName: 'key',
            render: null
        }
    },
    modal: {
        hasDivider: false
    },
    page: {
        small: false,
        size: 2,
        sizes: [2, 20, 50, 55],
        layout: 'total,pager,jumper,sizes',
        onChangeSize() {
            // The need to deal with the global paging page number
        },
        init() {
            // The need to deal with the global paging page number
        },
        onChange() {}
    },
    avatar: {
        handleSrc(src) {
            return src;
        }
    },
    datepicker: {
        startWeek: 1,
        format: {
            date: 'YYYY-MM-DD',
            month: 'YYYY-MM',
            year: 'YYYY',
            time: 'HH:mm',
            datetime: 'YYYY-MM-DD HH:mm',
            datehour: 'YYYY-MM-DD HH:mm',
            datetimesecond: 'YYYY-MM-DD HH:mm:ss'
        },
        datetimeOptions: {
            minuteStep: 5
        },
        daterangeOptions: {
            paramName: {
                start: 'start',
                end: 'end'
            }
        }
    }
};

 export default heyUIconf
