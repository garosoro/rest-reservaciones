export interface Table {
    id: number | null;
    number: string;
    capacity: number;
    status?: string;
    [key: string]: any;
}
