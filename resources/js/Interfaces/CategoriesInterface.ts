export interface categoryBaseI {
  uuid: string;
  code: string;
  name: string;
  description: string | null;
  created_at: string;
  updated_at: string;
}

export interface categoryPaginationI {
  current_page: number;
  data: categoryBaseI[];
  first_page_url: string | null;
  from: number;
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
}
