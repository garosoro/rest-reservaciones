// import DayJsAdapter from '@date-io/dayjs';
import '@mdi/font/css/materialdesignicons.css';
import { createVuetify } from 'vuetify';
import { VDateInput } from 'vuetify/labs/VDateInput';
import { VTimePicker } from 'vuetify/labs/VTimePicker';
import { aliases, mdi } from 'vuetify/lib/iconsets/mdi';
import 'vuetify/styles';

export default createVuetify({
    theme: {
        icons: {
            defaultSet: 'mdi',
            aliases,
            sets: {
                mdi,
            },
        },
    },
    // date: {
    //     adapter: DayJsAdapter,
    // },
    components: {
        VTimePicker,
        VDateInput,
    },
});
