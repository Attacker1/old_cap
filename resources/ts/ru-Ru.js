!function (e, t) {
    if ("object" == typeof exports && "object" == typeof module) module.exports = t(); else if ("function" == typeof define && define.amd) define([], t); else {
        var o = t();
        for (var r in o) ("object" == typeof exports ? exports : e)[r] = o[r]
    }
}(window, (function () {
    return function (e) {
        var t = {};

        function o(r) {
            if (t[r]) return t[r].exports;
            var a = t[r] = {i: r, l: !1, exports: {}};
            return e[r].call(a.exports, a, a.exports, o), a.l = !0, a.exports
        }

        return o.m = e, o.c = t, o.d = function (e, t, r) {
            o.o(e, t) || Object.defineProperty(e, t, {enumerable: !0, get: r})
        }, o.r = function (e) {
            "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {value: "Module"}), Object.defineProperty(e, "__esModule", {value: !0})
        }, o.t = function (e, t) {
            if (1 & t && (e = o(e)), 8 & t) return e;
            if (4 & t && "object" == typeof e && e && e.__esModule) return e;
            var r = Object.create(null);
            if (o.r(r), Object.defineProperty(r, "default", {
                enumerable: !0,
                value: e
            }), 2 & t && "string" != typeof e) for (var a in e) o.d(r, a, function (t) {
                return e[t]
            }.bind(null, a));
            return r
        }, o.n = function (e) {
            var t = e && e.__esModule ? function () {
                return e.default
            } : function () {
                return e
            };
            return o.d(t, "a", t), t
        }, o.o = function (e, t) {
            return Object.prototype.hasOwnProperty.call(e, t)
        }, o.p = "", o(o.s = 0)
    }([function (e, t, o) {
        e.exports = o(1)
    }, function (e, t, o) {
        "use strict";
        Object.defineProperty(t, "__esModule", {value: !0}), t.default = void 0;
        var r = {
            h: {
                locale: "en-US",
                common: {
                    cancel: "Отменить",
                    confirm: "Да",
                    clear: "Очистить",
                    nullOptionText: "Выберите",
                    empty: "пусто",
                    any: "any"
                },
                confirm: {title: "Да"},
                checkbox: {limitSize: "You can select up to {limitSize} data."},
                select: {
                    nullOptionText: "нужно выбрать",
                    placeholder: "выбрать",
                    emptyContent: "нет результатов",
                    searchPlaceHolder: "поиск",
                    limitSize: "Вы можете выбрать только {limitSize} шт."
                },
                category: {placeholder: "please choose"},
                cascader: {placeholder: "please choose"},
                categoryModal: {
                    limitWords: "You can select up to {size} data.",
                    emptyContent: "No results found",
                    total: "total"
                },
                categoryPicker: {
                    nullOptionText: "please choose",
                    placeholder: "выбрать",
                    total: "total",
                    limitSize: "You can select up to {0} data."
                },
                autoComplate: {placeholder: "Search Input", emptyContent: "No results found"},
                validation: {
                    base: {
                        required: " Не может быть пустым",
                        maxLen: " text length can't exceed {value} bits",
                        minLen: " text length can't be less than {value} bits",
                        max: " no more than {value}",
                        min: " can't be less than {value}"
                    },
                    type: {
                        int: " is not the correct integer format",
                        number: " is not the correct digital format",
                        email: " is not the correct mailbox format",
                        url: " is not the correct URL format",
                        tel: " is not the correct phone number format",
                        mobile: " is not the correct mobile number format",
                        globalmobile: " is not the correct international mobile number format"
                    }
                },
                date: {
                    today: "Сегодня",
                    yesterday: "Вчера",
                    year: "год",
                    month: "месяц",
                    week: "неделя",
                    quarter: "квартал",
                    day: "день",
                    header: {year: "", month: "", day: ""},
                    show: {
                        week: "{year} {weeknum}th week {daystart} - {dayend}",
                        weekInput: "{year} {week}th week",
                        quarter: "{year} {quarter}th quarter"
                    },
                    months: {
                        january: "Янв",
                        february: "Фев",
                        march: "Март",
                        april: "Апр",
                        may: "Май",
                        june: "Июнь",
                        july: "Июль",
                        august: "Авг",
                        september: "Сен",
                        october: "Окт",
                        november: "Ноя",
                        december: "Дек"
                    },
                    weeks: {
                        monday: "Пн",
                        tuesday: "Вт",
                        wednesday: "Ср",
                        thursday: "Чт",
                        friday: "Пт",
                        saturday: "Сб",
                        sunday: "Вс"
                    }
                },
                datepicker: {
                    placeholder: "Выберите",
                    startTime: "начало",
                    endTime: "конец",
                    customize: "изменить",
                    start: "начало",
                    end: "конец"
                },
                wordlimit: {warn: "You are limited to enter {0} words"},
                wordcount: {warn: "You have exceeded {0} words"},
                treepicker: {selectDesc: "You have selected {0} items", placeholder: "please select"},
                search: {placeholder: "поиск...", searchText: "Search"},
                taginput: {limitWords: "You have exceeded the limit"},
                table: {empty: "No results found"},
                uploader: {upload: "Upload", reUpload: "ReUpload"},
                pagination: {
                    incorrectFormat: "The format of the value you entered is incorrect",
                    overSize: "The value you entered exceeds the range",
                    totalBefore: "Всего",
                    totalAfter: "записей",
                    sizeOfPage: "{size} записей/страница"
                }
            }
        };
        t.default = r
    }]).default
}));
