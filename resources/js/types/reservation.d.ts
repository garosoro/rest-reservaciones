export interface Reservation {
    id: number | null;
    date: string;
    time: string;
    number_of_people?: number;
    diner_id?: string;
    table_id?: string;
    status?: string;
    [key: string]: any;
}
