export interface UserI {
  email: string;
  uuid: string;
  name: string;
  profile_photo_url: string;
  role: string;
  status: boolean;
}

export interface UserRoleI extends UserI {
  roles: RoleI[];
}

export interface userPaginationI {
  links: {
    first: string;
    last?: string;
    next?: string;
    prev?: string;
  };
  meta: {
    current_page: number;
    from: number;
    to: number;
    per_page: number;
  };
  data: UserI[];
}

export interface RoleI {
  uuid: string;
  name: string;
}
