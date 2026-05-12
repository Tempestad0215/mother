

export interface BrandBaseI {
  name: string;
  description: string | null;
}

export interface BrandFullI extends BrandBaseI{
  uuid: string;
}
