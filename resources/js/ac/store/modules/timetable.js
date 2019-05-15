const state = {
    days: {
        1: 'Понедельник',
        2: 'Вторник',
        3: 'Среда',
        4: 'Четверг',
        5: 'Пятница',
        6: 'Суббота',
        7: 'Воскресенье'
    },

    hoursOfLessons: {
        1: {
            start: '8:30',
            end: '9:10'
        },

        2: {
            start: '9:20',
            end: '10:00'
        },

        3: {
            start: '10:10',
            end: '10:50'
        },

        4: {
            start: '11:20',
            end: '12:00'
        },

        5: {
            start: '12:10',
            end: '12:50'
        },

        6: {
            start: '13:00',
            end: '13:40'
        },

        7: {
            start: '13:50',
            end: '14:30'
        },
    },

    subjects: [
        {id: 1, name: 'Русский язык'},
        {id: 2, name: 'Математика'},
        {id: 3, name: 'Литература'}
    ],

    currentWeek: null,

    currentWeekTimetable: [
        {
            id: 1,
            week: 20,
            day: 1,
            hour: 1,
            subject: 1,
            division: 9
        },

        {
            id: 2,
            week: 20,
            day: 1,
            hour: 2,
            subject: 2,
            division: 9
        },
    ]
}

const getters = {
    getCurrentWeek: state => {
        if (state.currentWeek !== null) return state.currentWeek

        let currentDate = new Date()
        let d = new Date(Date.UTC(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate()))
        let dayNum = d.getUTCDay() || 7
        d.setUTCDate(d.getUTCDate() + 4 - dayNum)
        let yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1))
        return Math.ceil((((d - yearStart) / 86400000) + 1) / 7)
    }
}

const mutations = {
    setCurrentWeek(state, week) {
        state.currentWeek = week
    }
}

const actions = {}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
