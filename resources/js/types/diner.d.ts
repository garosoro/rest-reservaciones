export interface Diner {
    id: number | null;
    name: string;
    email: string;
    telephone?: string;
    address?: string;
    [key: string]: any;
}
